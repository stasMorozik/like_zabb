import { Either, left, right } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { UseCase } from "../uses-cases/creating-code/use-case";
import { Errors } from '../../common/errors';
import { Dtos as CommonDtos } from "../../common/dtos";

export namespace Adapters {
  export class Emiter implements UseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Errors.ErrorI, CommonDtos.Message>>
    ){}

    emit(either: Either<Errors.ErrorI, CommonDtos.Message>): void {
      this._subject.next(either);
    }
  }

  export class Api implements UseCase.Ports.Api {
    fetch(dto: CommonDtos.Email): Observable<Either<Errors.ErrorI, boolean>> {
      return ajax({
        url: '/api/confirmation-codes/',
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: dto
      }).pipe(
        switchMap(() => {
          return of(right(true));
        }),
        catchError((error) => {
          //need middleware or interceptor
          if (error.status == 400) {
            return of(left(new Errors.Infrastructure.BadRequest(error.response.message)));
          }
          if (error.status == 404) {
            return of(left(new Errors.Infrastructure.NotFound(error.response.message)));
          }
          if (error.status == 500) {
            return of(left(new Errors.Infrastructure.InternalServerError(error.response.message)));
          }
          return of(right(true));
        })
      );
    }
  }
}
