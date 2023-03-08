import { Either } from "@sweet-monads/either";
import { Subject, Observable } from 'rxjs';
import { CreatingCodeUseCase } from '../use-cases/creating-code';
import { SharedDtos } from '../use-cases/shared/dtos';
import { notAuthFetch } from './shared/not-auth.fetch';

export namespace CreatingCodeAdapters {
  export class Emiter implements CreatingCodeUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either)
    }
  }

  export class Api implements CreatingCodeUseCase.Ports.Api {
    fetch(dto: SharedDtos.Email): Observable<Either<Error, boolean>> {
      return notAuthFetch<boolean>({
        url: '/api/confirmation-codes/',
        method: 'POST',
        body: dto
      })
    }
  }
}
