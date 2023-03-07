import { Either, left, right } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { RegistrationUseCase } from '../use-cases/registration';

export namespace RegistrationAdapters {
  export class Emiter implements RegistrationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either);
    }
  }

  export class Api implements RegistrationUseCase.Ports.Api {
    fetch(dto: RegistrationUseCase.Dtos.Data): Observable<Either<Error, boolean>> {
      return ajax({
        url: '/api/accounts/',
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
